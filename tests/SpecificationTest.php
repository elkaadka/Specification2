<?php

namespace Kanel\Specification2\Tests;

use Kanel\Specification2\Specification;
use Kanel\Specification2\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class SpecificationTest extends TestCase
{
    protected $trueSpecification;
    protected $falseSpecification;

    public function setUp()
    {
        $this->trueSpecification = new class implements SpecificationInterface {
            public function isValid(): bool
            {
                return true;
            }
        };
        $this->falseSpecification = new class implements SpecificationInterface {
            public function isValid(): bool
            {
                return false;
            }
        };
    }

    public function testIsSatisfiedBy()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = new Specification();
        $specification = $specification->isSatisfiedBy($this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testIsSatisfiedByAll()
    {
        $specification = (new Specification())->isSatisfiedByAll($this->trueSpecification, $this->trueSpecification, $this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedByAll($this->trueSpecification, $this->trueSpecification, $this->falseSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedByAll($this->falseSpecification, $this->falseSpecification, $this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testIsSatisfiedByAny()
    {
        $specification = (new Specification())->isSatisfiedByAny($this->trueSpecification, $this->trueSpecification, $this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedByAny($this->falseSpecification, $this->falseSpecification, $this->falseSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedByAny($this->trueSpecification, $this->falseSpecification, $this->falseSpecification);
        $this->assertTrue($specification->isValid());
    }

    public function testXor()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->xor($this->falseSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->xor($this->trueSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->xor($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->xor($this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testOr()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->or($this->falseSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->or($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->or($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->or($this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testAnd()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->and($this->falseSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->and($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->and($this->trueSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->and($this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testAndNot()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->andNot($this->trueSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->andNot($this->falseSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->andNot($this->trueSpecification);
        $this->assertFalse($specification->isValid());


        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->andNot($this->falseSpecification);
        $this->assertFalse($specification->isValid());
    }

    public function testOrNot()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->orNot($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->orNot($this->falseSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->orNot($this->trueSpecification);
        $this->assertFalse($specification->isValid());


        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->orNot($this->falseSpecification);
        $this->assertTrue($specification->isValid());
    }

    public function testXorNot()
    {
        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->xorNot($this->trueSpecification);
        $this->assertTrue($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->trueSpecification)->xorNot($this->falseSpecification);
        $this->assertFalse($specification->isValid());

        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->xorNot($this->trueSpecification);
        $this->assertFalse($specification->isValid());


        $specification = (new Specification())->isSatisfiedBy($this->falseSpecification)->xorNot($this->falseSpecification);
        $this->assertTrue($specification->isValid());
    }
}
